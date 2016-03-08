/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;

LOCK TABLES `user` WRITE;
INSERT INTO `user` VALUES (1,'Mr. Demo','demo','$2y$10$o9uHy5pPRy4n314R2.uJnOP6uJKevtiVTmHB56peFnCX6Ui8qpXyy');
UNLOCK TABLES;

LOCK TABLES `article` WRITE;
INSERT INTO `article` VALUES (1,1,'Welcome to demo','![Bear](https://placebear.com/780/320)\n\nYou are on the demo blog site. Use **demo:openme** for sign in and write (or edit) articles. For enabling comments edit **siteid** attribute of the **disqus** section in the **settings.ini** file.','<p><img src=\"https://placebear.com/780/320\" alt=\"Bear\" /></p>\n\n<p>You are on the demo blog site. Use <strong>demo:openme</strong> for sign in and write (or edit) articles. For enabling comments edit <strong>siteid</strong> attribute of the <strong>disqus</strong> section in the <strong>settings.ini</strong> file.</p>\n','<p><img src=\"https://placebear.com/780/320\" alt=\"Bear\"></p><p>You are on the demo blog site. Use <strong>demo:openme</strong> for sign in and write (or edit) articles. For enabling comments edit <strong>siteid</strong> attribute of the <strong>disqus</strong> section in the <strong>settings.ini</strong> file.</p>','2016-01-01 12:00:00');
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
