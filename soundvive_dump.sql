-- MySQL dump 10.13  Distrib 8.3.0, for Linux (x86_64)
--
-- Host: localhost    Database: soundvibe
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  `postId` int NOT NULL,
  `authorId` int NOT NULL,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,'i already read this article on the another portal',1,1,'2025-03-25 19:07:29'),(2,'i already read this article on the another portal',3,1,'2025-03-25 19:07:35'),(3,'i already read this article on the another portal',8,1,'2025-03-25 19:07:39'),(4,'i already read this article on the another portal',14,1,'2025-03-25 19:07:42'),(5,'this is realy sad, but i don\'t know',12,1,'2025-03-25 19:08:14'),(6,'so sad <3333',16,1,'2025-03-25 19:08:28'),(7,'I WAITED THIS MOMONET 3...  NO 4000 YEARS ',16,1,'2025-03-25 19:08:54'),(8,'I WAITED THIS MOMONET 3...  NO 4000 YEARS ',4,1,'2025-03-25 19:09:08'),(9,'ну нарешті, ми чекало цього занадто довго лол',1,2,'2025-03-25 19:10:02'),(10,'ну нарешті, ми чекало цього занадто довго лол',4,2,'2025-03-25 19:10:06'),(11,'I already read this article on another portal',1,3,'2025-03-25 19:07:29'),(12,'I already read this article on another portal',3,3,'2025-03-25 19:07:35'),(13,'I already read this article on another portal',8,3,'2025-03-25 19:07:39'),(14,'I already read this article on another portal',14,3,'2025-03-25 19:07:42'),(15,'This is really sad, but I don\'t know',12,4,'2025-03-25 19:08:14'),(16,'So sad <3333',16,4,'2025-03-25 19:08:28'),(17,'I WAITED FOR THIS MOMENT 3... NO 4000 YEARS',16,4,'2025-03-25 19:08:54'),(18,'I WAITED FOR THIS MOMENT 3... NO 4000 YEARS',4,4,'2025-03-25 19:09:08'),(19,'Ну нарешті, ми чекали цього занадто довго лол',1,5,'2025-03-25 19:10:02'),(20,'Ну нарешті, ми чекали цього занадто довго лол',4,5,'2025-03-25 19:10:06'),(21,'Це було неочікувано, але цікаво!',5,6,'2025-03-25 19:11:00'),(22,'Wow, amazing collaboration!',2,6,'2025-03-25 19:11:10'),(23,'Коли буде турне?',10,6,'2025-03-25 19:11:25'),(24,'Що думаєте про цей альбом?',3,6,'2025-03-25 19:11:40'),(25,'Не думаю, що це буде популярним, але цікава ідея.',13,3,'2025-03-25 19:12:05'),(26,'Вініл знову в тренді!',14,3,'2025-03-25 19:12:18'),(27,'Яка ваша улюблена пісня звідси?',8,3,'2025-03-25 19:12:30'),(28,'Фестивалі змінюються, цікаво що буде далі.',15,3,'2025-03-25 19:12:45'),(29,'damn, it was fire ',12,1,'2025-03-25 20:01:00'),(30,'як на мене досить цікаво, варто спробувати',13,7,'2025-03-26 18:47:41'),(31,'its already here heeeeelll YEEEAAh',1,7,'2025-03-26 18:49:32');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,'Рок-гурт випустив альбом у стилі електро-поп','Фанати шоковані: класичний рок-гурт представив електронний альбом. Музиканти пояснюють зміну стилю бажанням експериментувати та рухатися вперед.','musical-novelties',0),(2,'Хіп-хоп та класична музика: несподіване поєднання','Новий колабораційний альбом об\'єднав реперів та симфонічний оркестр, створюючи унікальне звучання. Проєкт викликав великий резонанс у музичній спільноті.','musical-novelties',0),(3,'Поп-зірка експериментує з джазом','Відома виконавиця здивувала слухачів, записавши альбом у стилі класичного джазу. Проєкт отримав схвальні відгуки від музичних критиків та відкрив нову грань її таланту.','musical-novelties',0),(4,'Легенда рок-музики випустила несподіваний альбом','Один з найвпливовіших музикантів нашого часу випустив альбом, який кардинально відрізняється від його попередніх робіт. Фани та критики розділилися в думках: одні вважають це шедевром, інші – невдалим експериментом.','musical-novelties',0),(5,'Рідкісний жанр отримав нове життя','Завдяки молодим ентузіастам, забутий музичний стиль повертається у маси. Незалежні лейбли почали активно підтримувати виконавців, які експериментують із традиційними мотивами та сучасним звучанням.','musical-discoveries',0),(6,'Акустична музика повертається у моду','Музичні критики зазначають, що інтерес до акустичної музики стрімко зростає. Виконавці, які використовують мінімалістичні аранжування та живі інструменти, набирають популярність серед слухачів, що втомилися від цифрового звучання.','musical-discoveries',0),(7,'Нове ім\'я в електронній музиці','Талановитий продюсер з маленького містечка отримав визнання після виходу свого першого альбому. Його нестандартний підхід до створення електронних композицій вже привернув увагу відомих артистів.','musical-discoveries',0),(8,'Молодий гурт підкорює світові чарти','Новий музичний колектив несподівано став вірусним, їхні пісні набирають мільйони прослуховувань. Критики прогнозують, що вони можуть стати наступною великою сенсацією у музичній індустрії.','musical-discoveries',0),(9,'Скандальний кліп викликав обурення у мережі','Новий відеокліп популярного виконавця спричинив хвилю критики через відверті сцени та провокаційний зміст. Одні називають це мистецтвом, інші вважають, що такі матеріали мають бути обмежені цензурою.','celebrities',0),(10,'Легендарний музикант анонсував прощальний тур','Після десятиліть на сцені культовий виконавець оголосив про свій останній гастрольний тур. Шанувальники з усього світу поспішають купити квитки, щоб востаннє почути улюблені хіти наживо.','celebrities',0),(11,'Новий роман між двома зірками?','Інсайдери повідомляють про можливі стосунки між двома популярними артистами. Папараці нещодавно помітили їх разом на вечірці, а шанувальники вже обговорюють потенційний розвиток подій у соціальних мережах.','celebrities',0),(12,'Скандал на премії Греммі: що сталося?','Відомий виконавець залишив церемонію після невдоволення рішенням журі. Шанувальники активно обговорюють ситуацію в соцмережах, висловлюючи підтримку артисту та висуваючи припущення про упередженість суддів.','celebrities',0),(13,'Новий додаток для фанатів музики','Стартап розробив платформу, яка допомагає слухачам знаходити музику за емоційним станом. Використовуючи алгоритми штучного інтелекту, додаток аналізує ваш настрій і пропонує плейлист, який максимально відповідає вашому емоційному стану.','general',0),(14,'Рекордні продажі вінілів у світі','Попит на вінілові платівки зростає. Сучасні слухачі все частіше віддають перевагу фізичним носіям, оскільки вони додають особливу атмосферу прослуховування.','general',0),(15,'Як змінюються формати музичних фестивалів?','Організатори фестивалів все частіше роблять ставку на екологічність, VR-технології та інтерактивні шоу. Деякі фестивалі навіть пропонують персоналізований музичний досвід для кожного відвідувача, використовуючи штучний інтелект та біометричні дані.','general',0),(16,'Музична індустрія у 2025: нові тенденції','Аналітики прогнозують, що штучний інтелект та віртуальні концерти стануть головними трендами у музиці цього року. Все більше артистів використовують AI для створення унікальних звуків.','general',0);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `sex` tinyint NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'bobik','bobik@gmail.com','02008e15c7b6d772c901227f20c79cb3b4f137a1659027a4287b98fb12bffcb2',1,'ROLE_USER'),(2,'bobik2','bobik2@gmail.com','f976819d2c60911331c2ee916a8bb9c192836d9225215258cd0e97a3c2d8ccc3',1,'ROLE_USER'),(3,'Daria','dariaa2@gmail.com','dfb4f361427d7542666b530175415f75a2ce31be65a74f38a1cc3bf15ee250c9',0,'ROLE_USER'),(4,'Reant','reantkram1352@gmail.com','70efb1d55d912f38dece2afe4a794bdbcdffd4a296d9dcca5c9975b3db66c919',1,'ROLE_USER'),(5,'Tim','timtim@gmail.com','cbb4b8ebad04479bff243d0365608645f7186a2b419ed2afa1901b7f8212831d',1,'ROLE_USER'),(6,'jake','jakexddd@gmail.com','e0291b484d9a204090d77b1de909269c1dc1f1b6593f377bdd9b02e29d7b155e',1,'ROLE_USER'),(7,'Kongroo','kongroo@gmail.com','b377658f83505395944f73545ab1468f1e040d0b9cf6afaa9829230ddc7aa70b',0,'ROLE_USER');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-10 11:53:02
