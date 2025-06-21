
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist de Discos de Roquito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: black;
            color: white;
        }
        
       .search-bar {
            margin: 20px auto;
            max-width: 600px;
        }

       .search-bar input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #333;
            color: white;
            font-size: 1rem;
        }

        .search-bar input::placeholder {
            color: white;
        }

       
        .card {
            margin: 20px 0;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background-color: #1c1c1c;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        .artist-image {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .album-image{
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }

        .spotify-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spotify-embed iframe {
            width: 100%;
            max-width: 500px;
            height: auto;
        }

        .song-list {
            list-style: none;
            padding: 0;
        }

        .song-list li {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        @media (max-width: 576px) {
            .card {
                margin: 10px 0;
            }
        }

        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <iframe src="https://gifer.com/embed/o6m" width=100 height=100 frameBorder="0" allowFullScreen></iframe>
        <h2 class="text-center mb-4">Mi Playlist</h2>
        
        <div class="search-bar">
            <input type="text" id="search-input" class="form-control" placeholder="Buscar por nombre de artista o álbum">
        </div>

        <div class="row" id="playlist-container">
            <?php
            $api_url = "https://roc.castillos.laboratoriodiseno.cl/js_castillo_rocio/wordpress/wp-json/wp/v2/posts?per_page=20";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                die("<div class='alert alert-danger'>Error al conectarse a la API REST: " . curl_error($ch) . "</div>");
            }

            curl_close($ch);

            $posts = json_decode($response, true);

            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $post_id = $post['id'];

                    $artist_image = get_field('img_autor', $post_id);  
                    $artist_name = get_field('nombre', $post_id);  
                    $album_name = get_field('nombre_album', $post_id);  
                    $album_image = get_field('foto_album', $post_id);  
                    $list = get_field('listado_canciones', $post_id);  
                    $spotify_embed = $post['content']['rendered'];  

                    echo "
                    <div class='col-12 col-sm-6 col-md-4 col-lg-3 card-item' data-artist='$artist_name' data-album='$album_name'>
                        <div class='card'>
                            <img src='$artist_image' class='artist-image' alt='Imagen del Artista'>
                            <div class='card-body'>
                                <h5 class='card-title'>$artist_name</h5>
                                <p class='card-text'><strong>Álbum:</strong> $album_name</p>
                                <div class='text-center'>
                                    <img src='$album_image' class='album-image' alt='Imagen del Álbum'>
                                </div>
                                <div class='mt-3'>
                                    <strong>Canciones:</strong>
                                    <ul class='song-list'>";
                                        if (!empty($list)) {
                                            $songs = explode(',', $list);  
                                            $songs = array_map('trim', $songs);  
                                            foreach ($songs as $song) {
                                                echo "<li>" . $song . "</li>";
                                            }
                                        } else {
                                            echo "<li>No hay canciones disponibles.</li>";
                                        }
                    echo "          </ul>
                                </div>
                                <div class='spotify-container mt-3'>
                                    $spotify_embed
                                </div> 
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='text-center'>No se encontraron entradas.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        
        $(document).ready(function() {
            $('#search-input').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                $('.card-item').each(function() {
                    var artist = $(this).data('artist').toLowerCase();
                    var album = $(this).data('album').toLowerCase();

                    if (artist.includes(searchTerm) || album.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
        
    </script>

</body>
</html>




