
/* criando uma tabela que armazenará os tweets dos usuários */

CREATE TABLE tweet (
	id_tweet int NOT null PRIMARY KEY AUTO_INCREMENT,
    id_usuario int NOT null,
    tweet varchar(140) NOT null,
    
    /* o banco de dados por default irá atribuir a data atual à inclusão do registro */
    
    data_inclusao datetime DEFAULT CURRENT_TIMESTAMP

);