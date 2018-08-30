
CREATE TABLE usuarios (
    
    id int NOT null PRIMARY KEY AUTO_INCREMENT,
    usuario varchar(50) NOT null,
    email varchar(100) NOT null,
	
    /* será um varchar de 32 caract. porque será usada uma criptografia que necessitará disso */
    senha varchar(32) NOT null    

);