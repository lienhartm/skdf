<?php
ob_start();
?>

<link rel="stylesheet" href="../style/article.css">

<section id="article">
    <div style="margin: 20px 50px;display: flex;flex-direction: row;align-items: space-between;">
        <form method="post" enctype="multipart/form-data" action="?action=write_article" style="width: 50%;">
            <h2>Ajouter un article</h2>
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
            <label for="file-image">Image</label>
            <input type="file" id="file-image" name="file[]" multiple>
            <input type="submit" value="Ajouter">
        </form>

        <div style="width: 50%;">
            <h2>Aperçu de l'article</h2>
            <div id="apercu-article"
                style="margin: 20px 50px; padding: 20px; border: 1px solid #ccc; background-color: white; border-radius: 5px; min-height: 50px;">
            </div>
            <div id="apercu-images"
                style="display: flex; flex-wrap: wrap; gap: 10px; margin: 20px 50px; padding: 20px; border: 1px solid #ccc; background-color: white; border-radius: 5px; min-height: 50px;">
            </div>
        </div>
    </div>

    <h2>Liste des articles</h2>
    <ul id="list-article">

    </ul>
</section>

<script>

    fetch('../documents/actualite/actus.json')
        .then(response => response.json())
        .then(data => {
            const actualitesContainer = document.getElementById('list-article');
            const path = '../documents/actualite/';
            data.forEach(article => {
                const articleDiv = document.createElement('li');
                articleDiv.classList.add('article');

                articleDiv.innerHTML = `${article.titre}
                <a href="">Modifier</a>
                <a href="">Supprimer</a>`;
                actualitesContainer.appendChild(articleDiv);
            });
        })
        .catch(error => console.error("Erreur lors du chargement des articles:", error));


    document.addEventListener('DOMContentLoaded', function () {
        // Markdown Preview Logging
        const contentInput = document.getElementById('content');
        const apercuDiv = document.getElementById('apercu-article');

        contentInput.addEventListener('input', function () {
            apercuDiv.innerHTML = marked.parse(contentInput.value);
        });

        // Image Upload Preview and Remove Logic
        const fileInput = document.getElementById('file-image');
        const listImages = document.getElementById('apercu-images');

        // Store selected files in an array since FileList is read-only
        let selectedFiles = [];

        fileInput.addEventListener('change', function (e) {
            const newFiles = Array.from(e.target.files);
            selectedFiles = selectedFiles.concat(newFiles);
            updateImageList();
        });

        function updateImageList() {
            listImages.innerHTML = ''; // Clear previous previews

            const dataTransfer = new DataTransfer();

            selectedFiles.forEach((file, index) => {
                dataTransfer.items.add(file);

                // Only process image files
                if (!file.type.match('image.*')) return;

                const reader = new FileReader();

                reader.onload = function (e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.style.position = 'relative';
                    imgContainer.style.width = '100px';
                    imgContainer.style.height = '100px';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.title = file.name;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '5px';
                    img.style.border = '1px solid #ccc';

                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.style.position = 'absolute';
                    removeBtn.style.top = '-5px';
                    removeBtn.style.right = '-5px';
                    removeBtn.style.background = 'red';
                    removeBtn.style.color = 'white';
                    removeBtn.style.border = 'none';
                    removeBtn.style.borderRadius = '50%';
                    removeBtn.style.cursor = 'pointer';
                    removeBtn.style.width = '20px';
                    removeBtn.style.height = '20px';
                    removeBtn.style.lineHeight = '20px';
                    removeBtn.style.padding = '0';
                    removeBtn.style.fontSize = '14px';

                    removeBtn.addEventListener('click', function (event) {
                        event.preventDefault(); // Prevent form submission
                        // Remove file from our array
                        selectedFiles.splice(index, 1);
                        // Re-render the list and update input
                        updateImageList();
                    });

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    listImages.appendChild(imgContainer);
                }

                reader.readAsDataURL(file);
            });

            // Update the actual file input with the modified list
            fileInput.files = dataTransfer.files;
        }
    });
</script>

<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>