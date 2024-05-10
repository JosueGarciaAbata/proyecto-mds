SELECT DISTINCT p.id_post, p.titulo_post, p.contenido_textual_post, p.ubicacion_imagen_post, p.id_categoria_post, p.id_estado_post
            FROM posts p
            INNER JOIN etiquetas_agrupadas ea ON p.id_post = ea.id_post_etiquetas_agrupadas
            INNER JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            WHERE p.id_categoria_post = 2
            AND e.id_etiqueta IN (8,9,10)
            AND p.id_estado_post = 1