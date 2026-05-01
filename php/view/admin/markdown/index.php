<?php
ob_start();
?>

<link href="../style/markdown.css" rel="stylesheet">
<script src="../script/marked.min.js"></script>

<section class="markdown-doc">
    <h2>Syntaxe Markdown</h2>
    <p>Voici un résumé des balises Markdown courantes que vous pouvez utiliser pour formater votre texte :</p>

    <table class="markdown-table">
        <thead>
            <tr>
                <th>Élément</th>
                <th>Syntaxe Markdown (à taper)</th>
                <th>Rendu (Résultat visuel)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Titres</td>
                <td>
                    <code># Titre 1</code><br>
                    <code>## Titre 2</code><br>
                    <code>### Titre 3</code>
                </td>
                <td>
                    <h1 style="font-size: 1.8em; margin: 0; font-weight: bold;color:black;text-shadow:inherit;">Titre 1
                    </h1>
                    <h2 style="font-size: 1.5em; margin: 0; border: none; padding: 0; color: #111; font-weight: bold;">
                        Titre 2</h2>
                    <h3 style="font-size: 1.17em; margin: 0; color: #111; font-weight: bold;">Titre 3</h3>
                </td>
            </tr>
            <tr>
                <td>Texte en Gras</td>
                <td><code>**Texte en gras**</code> ou <code>__Gras__</code></td>
                <td><strong>Texte en gras</strong></td>
            </tr>
            <tr>
                <td>Texte en Italique</td>
                <td><code>*Texte en italique*</code> ou <code>_Italique_</code></td>
                <td><em>Texte en italique</em></td>
            </tr>
            <tr>
                <td>Listes à puces</td>
                <td>
                    <code>- Élément 1</code><br>
                    <code>- Élément 2</code><br>
                    <code>- Élément 3</code>
                </td>
                <td>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Élément 1</li>
                        <li>Élément 2</li>
                        <li>Élément 3</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>Listes numérotées</td>
                <td>
                    <code>1. Premier élément</code><br>
                    <code>2. Deuxième élément</code>
                </td>
                <td>
                    <ol style="margin: 0; padding-left: 20px;">
                        <li>Premier élément</li>
                        <li>Deuxième élément</li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td>Liens Internet</td>
                <td><code>[Texte du lien](https://www.exemple.com)</code></td>
                <td><a href="#" style="color: #0d6efd; text-decoration: underline;">Texte du lien</a></td>
            </tr>
            <tr>
                <td>Citations</td>
                <td><code>> Ceci est une citation</code></td>
                <td>
                    <blockquote
                        style="margin: 0; border-left: 4px solid #ccc; padding-left: 10px; color: #666; font-style: italic;">
                        Ceci est une citation</blockquote>
                </td>
            </tr>
            <tr>
                <td>Code (en ligne)</td>
                <td>Ceci est du <code>`code en ligne`</code></td>
                <td>Ceci est du <code
                        style="background-color: #f4f4f4; padding: 2px 4px; border-radius: 3px; font-family: monospace; color: #333;">code en ligne</code>
                </td>
            </tr>
            <tr>
                <td>Ligne de séparation</td>
                <td><code>---</code></td>
                <td>
                    <hr style="border: 0; border-top: 1px solid #ccc; margin: 10px 0;">
                </td>
            </tr>
            <tr>
                <td>Tableau</td>
                <td>
                    <code>
                        | Option | Description |<br>
                        | ------ | ----------- |<br>
                        | 1 | un  |<br>
                        | 2 | deux |<br>
                        | 3 | trois |
                    </code>
                </td>
                <td>
                    <table class="doc">
                        <thead>
                            <tr>
                                <th>Option</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>un</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>deux</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>trois</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Image</td>
                <td><code>![Ski de bande dessinée de pingouin](https://img.freepik.com/vecteurs-premium/ski-bande-dessinee-pingouin_43633-756.jpg)</code>
                </td>
                <td><img src="https://img.freepik.com/vecteurs-premium/ski-bande-dessinee-pingouin_43633-756.jpg"
                        alt="Ski de bande dessinée de pingouin" width="200px"></td>
            </tr>
            <tr>
                <td>Inserted text</td>
                <td><code>++Inserted text++</code></td>
                <td><ins>Inserted text</ins></td>
            </tr>
            <tr>
                <td>Marked text</td>
                <td><code>==Marked text==</code></td>
                <td><mark>Marked text</mark></td>
            </tr>
            <tr>
                <td>Footnote</td>
                <td><code>Footnote 1 link[^first].</code></td>
                <td>Footnote 1 link<a href="#fn1" id="fnref1">[1]</a></td>
            </tr>
            <tr>
                <td>Abréviation</td>
                <td><code>This is HTML abbreviation example.<br>*[HTML]: Hyper Text Markup Language</code></td>
                <td>This is <abbr title="Hyper Text Markup Language">HTML</abbr> abbreviation example.</td>
            </tr>
            <tr>
                <td>Customs containers</td>
                <td>
                    <code>
                        ::: warning<br>
                        *here be dragons*<br>
                        :::
                    </code>
                </td>
                <td>
                    <div class="warning">
                        <span style="font-style: normal;padding: 0 10px;">⚠️</span> here be dragons
                    </div>
                </td>
            </tr>
            <tr>
                <td>Definition list</td>
                <td><code>
                    Term 1<br>
                    ~ Definition 1
                </code></td>
                <td>
                    <dl>
                        <dt>Term 1</dt>
                        <dd>Definition 1</dd>
                    </dl>
                </td>
            </tr>
        </tbody>
    </table>

    <p id="fn1">Footnote 1</p>

    <h3>Customs containers</h3>
    <table class="doc">
        <tr>
            <td>Info</td>
            <td>Bleu</td>
            <td>ℹ️</td>
        </tr>
        <tr>
            <td>Success</td>
            <td>Vert</td>
            <td>✅</td>
        </tr>
        <tr>
            <td>Warning</td>
            <td>Orange/Jaune</td>
            <td>⚠️</td>
        </tr>
        <tr>
            <td>Error</td>
            <td>Rouge</td>
            <td>❌</td>
        </tr>
        <tr>
            <td>Debug</td>
            <td>Gris</td>
            <td>🐞</td>
        </tr>
        <tr>
            <td>Security</td>
            <td>Violet</td>
            <td>🔒</td>
        </tr>
        <tr>
            <td>Tip</td>
            <td>Turquoise</td>
            <td>💡</td>
        </tr>
        <tr>
            <td>Maintenance</td>
            <td>Orange</td>
            <td>🚧</td>
        </tr>
        <tr>
            <td>Critical</td>
            <td>Rouge foncé</td>
            <td>❗</td>
        </tr>
    </table>

    <hr>

    <h2>Démonstration Interactive</h2>
    <p>Essayez vous-même ! Tapez du texte avec la syntaxe de la table ci-dessus à gauche, et voyez le résultat
        en temps réel à droite :</p>

    <div class="demo-container">
        <div class="demo-box">
            <textarea id="editeur-demo"># Titre de l'article

Voici une introduction avec un mot **important** et une expression *mise en valeur*.

## Notre liste des courses

- Pain
- Fromage
- Vin blanc

## Les étapes

1. Se lever
2. Mettre les skis
3. Profiter de la neige !

> Le ski de fond, c'est la santé !

Pour plus d'infos, visitez [notre page des actus](actualites.html).</textarea>
        </div>
        <div class="demo-box" id="apercu-demo">
            <!-- Le HTML généré ira ici -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editeur = document.getElementById('editeur-demo');
            const apercu = document.getElementById('apercu-demo');

            function majApercu() {
                apercu.innerHTML = marked.parse(editeur.value);
            }

            // Mettre à jour à chaque frappe au clavier
            editeur.addEventListener('input', majApercu);
            // Rendu initial
            majApercu();
        });
    </script>
</section>

<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>