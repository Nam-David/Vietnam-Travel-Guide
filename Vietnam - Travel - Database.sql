DROP table users
DROP table province
DROP table destination
DROP table post
DROP table blog
DROP table imgBlog

-- auto increment in Pgadmin 
-- relation SEQUENCE is implicit to SERIAL type, must be the same same order as table
CREATE SEQUENCE user_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1; --cache for faster add

CREATE SEQUENCE province_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 63
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE destination_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE 
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE post_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE 
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE post_detail_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE 
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE blog_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE 
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE imgBlog_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE 
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE userComment_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE 
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE repComment_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE 
    NO MINVALUE
    CACHE 1;
--------------------------------------------------------------

CREATE table users (
	--userID user serial from function above
    userID SERIAL  PRIMARY KEY,
	userName VARCHAR (50) NOT NULL UNIQUE, 
	pass_word VARCHAR (20) NOT NULL, 
	role_ VARCHAR (50) NOT NULL,
	email VARCHAR (50) NOT NULL UNIQUE
);

CREATE table province (
	--userID user serial from function above
    provinceID SERIAL PRIMARY KEY ,

	provinceName VARCHAR (50) NOT NULL UNIQUE, 
	provinceRegion VARCHAR (20) NOT NULL  --in range North Middle South
);

CREATE table destination (
	--userID user serial from function above
    destinationID SERIAL  PRIMARY KEY ,
	provinceID INT NOT NULL, 
	destinationName VARCHAR (50) NOT NULL, 
	destinationContent TEXT NOT NULL,
	imgDesURL TEXT NOT NULL ,
	FOREIGN KEY (provinceID) REFERENCES province(provinceID)
);

CREATE table post(
	--userID user serial from function above
    postID SERIAL PRIMARY KEY,
	provinceID INT NOT NULL, 
	postCreateDate DATE NOT NULL,
	imgPostURL TEXT NOT NULL,
	FOREIGN KEY (provinceID) REFERENCES province(provinceID)
);

CREATE table postDetail(
    postDetailID SERIAL PRIMARY KEY,
	postID INT NOT NULL,
	sectionTitle TEXT NOT NULL,
	sectionContent TEXT NOT NULL,
	--category: food, travel,....
	category VARCHAR(20) NOT NULL,
	imgPostDetURL TEXT NOT NULL,
	FOREIGN KEY (postID) REFERENCES post(postID)
);

CREATE table blog(
    blogID SERIAL PRIMARY KEY,
	provinceID INT NOT NULL,
	userID INT NOT NULL,
	blogContent TEXT NOT NULL,
	blogCreateDate DATE NOT NULL,
	
	FOREIGN KEY (userID) REFERENCES users(userID),
	FOREIGN KEY (provinceID) REFERENCES province(provinceID)
);

CREATE table imgBlog(
	--userID user serial from function above
    imgID SERIAL PRIMARY KEY,
	blogID INT NOT NULL, 
	imgBlogURL TEXT NOT NULL,
	FOREIGN KEY (blogID) REFERENCES blog(blogID)
);

CREATE table userComment (
    commentID SERIAL PRIMARY KEY,
	blogID INT NOT NULL, 
	userID INT NOT NULL, 
	cmtContent TEXT NOT NULL,
	createDate DATE NOT NULL,

	FOREIGN KEY (blogID) REFERENCES blog(blogID),
	FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE table repComment (
    repCommentID SERIAL PRIMARY KEY,
	commentID INT NOT NULL, 
	userID INT NOT NULL, 
	repContent TEXT NOT NULL,
	createDateRep DATE NOT NULL,

	FOREIGN KEY (commentID) REFERENCES userComment(commentID),
	FOREIGN KEY (userID) REFERENCES users(userID)
);

-- cac bang co AUTO_INCREMENT ma la Foreign Key, van can phai dien vao trong database 


---------------------------------------------------------------------
INSERT INTO users (userName, pass_word, role_, email )VALUES
('Nam','789','President','anotheragency@example.com'),
('David','789','CEO','anothermail@example.com');

INSERT INTO province (provinceName, provinceRegion )VALUES
('Ha Noi','North'),
('Da Nang','Middle'),
('Ho Chi Minh City','South');

INSERT INTO destination (provinceID, destinationName, destinationContent, imgDesURL )VALUES
(1,'Ho Hoan Kiem','World biggest cave', 'https://lucid.app/lucidchart/d0fa0d69-981b-40e5-bd84-967ed082ae2a/edit?invitationId=inv_ec1e735a-82eb-45f7-8f57-614c7cb08cf6&page=UWoltS0NEWLp#'),
(2,'My Khe','Top most beautiful beach in the world', 'https://cloudinary.com/'),
(3,'Ho Chi Minh City', 'Vibrant city','https://www.youtube.com/watch?v=cw93HyeOTeQ&t=1564s&ab_channel=AlanWalker'),
(3,'Pho Di Bo', 'Vibrant city','https://www.figma.com/design/eZphw0DGLir7oysXGSPcMj/Untitled?node-id=0-1&node-type=canvas&t=8MLNXeRiaHkHDaXq-0	'),
(2,'Cau Rong', 'Worth Living City','https://gemini.google.com/app/ee00ccfaa73859aa');

INSERT INTO post(provinceID, postCreateDate, imgPostURL )VALUES
(1,'20/11/2024', 'https://www.udemy.com/'),
(2,'20/11/2024', 'https://edition.cnn.com/'),
(3,'20/11/2024','https://www.vndirect.com.vn/'),
(3,'20/11/2024','https://gdg.community.dev/');

INSERT INTO postDetail(postID, sectionTitle, sectionContent, category, imgPostDetURL )VALUES
(1,'Bun cha Ha Noi tuyet voi', 'ngonnnnnnnnnnnnnnnnnnnnn', 'food', 'https://edition.cnn.com/'),
(1,'Ha Noi choi gi', 'ho guom', 'travel', 'https://edition.cnn.com/'),
(1,'Ha Noi di chuyen', 'tau dien va xe may vinfast', 'transport', 'https://edition.cnn.com/'),
(2,'O Da Nang vui','vuiiiiiiiiiiiii','travel' ,'https://gdg.community.dev/'),
(3,'An gi o Sai Gon','qua troi','food' ,'https://gdg.community.dev/');

INSERT INTO blog(provinceID, userID, blogContent, blogCreateDate )VALUES
(1,1, 'Hanoi - Capital of Vietnam', '20/11/2024'),
(1,2,'Hanoi - 1000 years capital', '20/11/2024'),
(3,1,'Ho Chi Minh City - Must travel City', '20/11/2024'),
(3,1,'Food paradise  in HCMC ', '20/11/2024');

INSERT INTO userComment(blogID, userID, cmtContent, createDate )VALUES
(1,1, 'Hanoi - Capital of Vietnam', '20/11/2024'),
(1,2,'Hanoi - 1000 years capital', '20/11/2024'),
(1,1,'Ho Chi Minh City - Must travel City', '20/11/2024'),
(2,1,'Food paradise  in HCMC ', '20/11/2024');

INSERT INTO repComment(provinceID, userID, blogContent, blogCreateDate )VALUES
(1,1, 'Hanoi - Capital of Vietnam', '20/11/2024'),
(1,2,'Hanoi - 1000 years capital', '20/11/2024'),
(3,1,'Ho Chi Minh City - Must travel City', '20/11/2024'),
(3,1,'Food paradise  in HCMC ', '20/11/2024');

select * from postDetail
select * from blog

select *  from post


