-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: taskmanagement
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Satyajit D','satya@gmail.com','Viewer',''),(2,'Sameer S','sameer@gmail.com','Viewer','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;


--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Publicity','Publicity','2025-09-13 15:43:06'),(2,'Technology','Technology','2025-09-13 15:43:20');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tasktypes`
--

LOCK TABLES `tasktypes` WRITE;
/*!40000 ALTER TABLE `tasktypes` DISABLE KEYS */;
INSERT INTO `tasktypes` VALUES (1,'Purchases','Purchases','2025-09-13 15:44:50'),(2,'Approval','Approval','2025-09-13 15:45:05');
/*!40000 ALTER TABLE `tasktypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `workweeks`
--

LOCK TABLES `workweeks` WRITE;
/*!40000 ALTER TABLE `workweeks` DISABLE KEYS */;
INSERT INTO `workweeks` VALUES (1,'01-Aug-2025 to 09-Aug-2025','August','2025-09-13 15:46:12'),(2,'01-Sep-2025 to 09-Sep-2025','September','2025-09-13 15:46:52'),(3,'10-Sep-2025 to 16-Sep-2025','September','2025-09-13 15:47:07');
/*!40000 ALTER TABLE `workweeks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,NULL,1,NULL,'New Task','Task description',1,2,1,'Pending',NULL,NULL,'2025-09-13 15:26:32'),(2,NULL,2,1,'New Task 2 is updated','Task 2 description',2,2,2,'Completed',NULL,NULL,'2025-09-13 15:38:18'),(3,NULL,2,NULL,'New Task 3','Task 3 description',1,1,2,'Pending',NULL,NULL,'2025-09-13 16:55:29'),(4,NULL,1,NULL,'Today new task complete task list new task dialog',NULL,2,2,1,'Hold',NULL,NULL,'2025-09-14 07:34:42'),(5,NULL,2,NULL,'Add functionality to Menu',NULL,1,1,NULL,'Pending',NULL,NULL,'2025-09-14 09:31:49'),(11,NULL,1,NULL,'Test for base url','base URL',1,1,NULL,'Pending',NULL,NULL,'2025-09-14 10:35:56');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-14 20:57:19
