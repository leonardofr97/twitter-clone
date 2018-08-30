

CREATE TABLE usuarios_seguidores (
    id_usuario_seguidor int NOT null PRIMARY KEY AUTO_INCREMENT,
    id_usuario int NOT null,
    seguindo_id_usuario int NOT null,
    data_registro datetime DEFAULT CURRENT_TIMESTAMP
    
    );