<?php
ob_start();
?>

<link rel="stylesheet" href="../style/newsletter.css">

<?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash-message" style="margin: 20px 50px; padding: 12px; background: #f4f4f4; border-left: 4px solid #0073e6;">
        <?php echo htmlspecialchars($_SESSION['flash'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<div style="margin: 20px 50px;display: flex;flex-direction: row;align-items: space-between;">
    <form method="post" enctype="multipart/form-data" action="?action=write_newsletter" style="width: 50%;">
        <h2>Publier une newsletter</h2>
        <label for="author">Auteur</label>
        <input type="text" id="author" name="author">
        <label for="date">Date</label>
        <input type="date" id="date" name="date">
        <label for="type">Type d'article :
            <select id="type" name="type">
                <option value="refuge">Refuge</option>
                <option value="club">Club</option>
                <option value="event">Événement</option>
            </select>
        </label>
        <label for="title">Titre</label>
        <input type="text" id="title" name="title">
        <label for="content">Contenu</label>
        <textarea id="content" name="content"></textarea>
        <label for="file-document">Document</label>
        <input type="file" id="file-document" name="file[]" accept=".pdf,.doc,.docx,.txt" multiple>
        <input type="submit" value="Ajouter">
    </form>

    <div style="width: 50%;">
        <h2>Aperçu de la newsletter</h2>
        <div id="apercu-article"
            style="margin: 20px 50px; padding: 20px; border: 1px solid #ccc; background-color: white; border-radius: 5px; min-height: 50px;">
        </div>
        <div id="apercu-documents"
            style="margin: 20px 50px; padding: 20px; border: 1px solid #ccc; background-color: white; border-radius: 5px; min-height: 50px;">
        </div>
    </div>

</div>

<script>


    document.addEventListener('DOMContentLoaded', function () {
        // Markdown Preview Logging
        const contentInput = document.getElementById('content');
        const apercuDiv = document.getElementById('apercu-article');

        function renderPreview() {
            apercuDiv.innerHTML = marked.parse(contentInput.value || '');
        }

        contentInput.addEventListener('input', renderPreview);
        renderPreview();

        // Document Upload Preview and Remove Logic
        const fileInput = document.getElementById('file-document');
        const listDocuments = document.getElementById('apercu-documents');

        // Store selected files in an array since FileList is read-only
        let selectedFiles = [];

        fileInput.addEventListener('change', function (e) {
            const newFiles = Array.from(e.target.files);
            selectedFiles = selectedFiles.concat(newFiles);
            updateDocumentList();
        });

        function updateDocumentList() {
            listDocuments.innerHTML = ''; // Clear previous previews

            // Create a new DataTransfer object to update the actual file input
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach((file, index) => {
                dataTransfer.items.add(file);

                const item = document.createElement('div');
                item.style.display = 'flex';
                item.style.justifyContent = 'space-between';
                item.style.alignItems = 'center';
                item.style.padding = '10px';
                item.style.border = '1px solid #ccc';
                item.style.borderRadius = '5px';
                item.style.marginBottom = '10px';
                item.style.backgroundColor = '#fafafa';

                const label = document.createElement('span');
                label.textContent = file.name;
                label.style.flex = '1';
                label.style.marginRight = '10px';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.textContent = 'Supprimer';
                removeBtn.style.background = 'red';
                removeBtn.style.color = 'white';
                removeBtn.style.border = 'none';
                removeBtn.style.borderRadius = '4px';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.padding = '5px 10px';

                removeBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    selectedFiles.splice(index, 1);
                    updateDocumentList();
                });

                item.appendChild(label);
                item.appendChild(removeBtn);
                listDocuments.appendChild(item);
            });

            fileInput.files = dataTransfer.files;
        }
    });
</script>


<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>