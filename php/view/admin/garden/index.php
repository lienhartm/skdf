<?php
ob_start();
?>

    <link href="../style/garden.css" rel="stylesheet">
    <link href="https://unpkg.com/@fullcalendar/core@6.1.8/index.global.css" rel="stylesheet">
    <script src="https://unpkg.com/@fullcalendar/core@6.1.8/index.global.js"></script>
    <script src="https://unpkg.com/@fullcalendar/daygrid@6.1.8/index.global.js"></script>
    <script src="https://unpkg.com/@fullcalendar/interaction@6.1.8/index.global.js"></script>


        <section class="garden">
          <div class="garden">
            <h2>📅 Planning des gardiens</h2>

            <div id="calendar"></div>

            <br />

            <form id="formGarde">

              <label>Gardien principal :</label>
              <select id="gardienSelect"></select>

              <label>Date début :</label>
              <input type="date" id="dateDebut" required>

              <label>Date fin :</label>
              <input type="date" id="dateFin" required>

              <button type="button" id="addGardien">➕ Ajouter un gardien</button>

              <div id="extras"></div>

              <button type="submit">Créer la garde</button>

            </form>

            <br />

          </div>
          <br />
        </section>

<script>
// ==========================
// Données
// ==========================
function getColor(index) {
  const hue = (index * 137.508) % 360;
  return `hsla(${hue}, 65%, 55%, 0.5)`; // 👈 transparence ici
}

// fonction texte lisible
function getTextColor(hsl) {
  const components = hsl.replace(/hsla?\(|\)|\s+/g, '').split(',');
  const lightness = parseFloat(components[2] || 55);

  return lightness > 60 ? "#000" : "#fff";
}

// amelioration
/*
  backgroundColor: color + "CC", // légère transparence
  borderColor: color
*/

const gardiens = <?php echo json_encode($gardiens ?? []); ?>;
const events = (<?php echo json_encode($events ?? []); ?> || []).map(event => {
  const color = event.color || getColor(parseInt(event.mainGardienId || 0, 10));
  return {
    ...event,
    backgroundColor: color,
    borderColor: color.replace("0.25", "1"),
    textColor: getTextColor(color)
  };
});

// ==========================
// Init select
// ==========================
const select = document.getElementById("gardienSelect");

if (gardiens && gardiens.length > 0) {
  gardiens.forEach(g => {
    if (g && g.id && g.nom && g.prenom) {
      let opt = document.createElement("option");
      opt.value = g.id;
      opt.textContent = g.nom + " " + g.prenom;
      select.appendChild(opt);
    }
  });
} else {
  console.warn("Aucun gardien disponible");
}

// ==========================
// Calendar
// ==========================
const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
  initialView: 'dayGridMonth',
  locale: 'fr',
  height: "auto",

  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: ''
  },

  events: events || [],
  eventClick: function(info) {
    if (!confirm('Supprimer cette garde ?')) {
      return;
    }

    const eventId = parseInt(info.event.id, 10);
    if (!eventId) {
      alert('Impossible de supprimer cette garde. Identifiant manquant.');
      return;
    }

    fetch('?action=delete_garden', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: eventId })
    }).then(response => response.json())
      .then(data => {
        if (!data.success) {
          alert(data.message || 'Erreur de suppression');
          return;
        }

        info.event.remove();
      })
      .catch(() => {
        alert('Impossible de supprimer la garde.');
      });
  }
});

calendar.render();

// ==========================
// Ajouter gardien extra
// ==========================
document.getElementById("addGardien").addEventListener("click", () => {
  const div = document.createElement("div");
  div.className = "extra";

  const sel = document.createElement("select");

  if (gardiens && gardiens.length > 0) {
    gardiens.forEach(g => {
      if (g && g.id && g.nom) {
        let opt = document.createElement("option");
        opt.value = g.id;
        opt.textContent = g.nom + " " + g.prenom;
        sel.appendChild(opt);
      }
    });
  }

  const removeBtn = document.createElement("button");
  removeBtn.type = "button";
  removeBtn.textContent = "❌ Supprimer";
  removeBtn.style.marginLeft = "10px";
  removeBtn.addEventListener("click", () => {
    div.remove();
  });

  div.appendChild(sel);
  div.appendChild(removeBtn);
  document.getElementById("extras").appendChild(div);
});

// ==========================
// Création garde
// ==========================
document.getElementById("formGarde").addEventListener("submit", function(e) {
  e.preventDefault();

  const debut = document.getElementById("dateDebut").value;
  const fin = document.getElementById("dateFin").value;

  if (fin <= debut) {
    alert("La date de fin doit être après le début");
    return;
  }

  // gardien principal
  const mainId = parseInt(select.value, 10);
  console.log("Gardien principal ID:", mainId);
  const main = gardiens.find(g => g && parseInt(g.id, 10) === mainId);

  console.log("Gardien principal trouvé:", main);
  console.log("Tous les gardiens disponibles:", gardiens);

  if (!main) {
    alert("Gardien principal invalide");
    return;
  }

  // extras
  const extraSelects = document.querySelectorAll("#extras select");

  const extras = Array.from(extraSelects).map(sel => parseInt(sel.value, 10));

  const selectedExtras = extras.map(id => gardiens.find(x => parseInt(x.id, 10) === id)).filter(Boolean);
  const tous = [main, ...selectedExtras];

  // titre
  const title = tous.map(g => g.nom).join(", ");

  // couleur (celle du premier)
  const color = main.couleur || getColor(parseInt(main.id, 10));
  const textColor = getTextColor(color);

  const eventData = {
    mainId: mainId,
    extras: extras,
    title: title,
    start: debut,
    end: fin,
    color: color,
    textColor: textColor
  };

  fetch('?action=save_garden', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(eventData)
  }).then(response => response.json())
    .then(data => {
      if (!data.success) {
        alert(data.message || 'Erreur enregistrement garde');
        return;
      }

      const eventColor = data.event.color || getColor(parseInt(data.event.mainGardienId || 0, 10));
      const savedEvent = {
        ...data.event,
        color: eventColor
      };
      calendar.addEvent(savedEvent);
      document.getElementById("formGarde").reset();
      document.getElementById("extras").innerHTML = "";
    })
    .catch(() => {
      alert('Impossible de sauvegarder la garde. !!!');
    });
});

</script>

<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>