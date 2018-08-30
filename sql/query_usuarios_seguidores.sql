
SELECT u.*, us.*
FROM usuarios AS u
LEFT JOIN usuarios_seguidores AS us ON ( us.id_usuario = 4 AND u.id = us.seguindo_id_usuario )
WHERE u.usuario like '%a%' AND u.id <> 4