create table `user` (
    `id` int unsigned not null auto_increment,
    `nickname` varchar(255) not null,
    `username` varchar(255) not null,
    `password` varchar(255) not null,
    `role` tinyint not null default 1,
    primary key (`id`),
    unique key (`nickname`)
) engine = innodb default charset = utf8;

create table `article` (
    `id` int unsigned not null auto_increment,
    `author_id` int unsigned not null,
    `title` varchar(255) not null,
    `body_raw` longtext not null,
    `body_html` longtext not null,
    `teaser` longtext not null,
    `created_at` datetime not null,
    primary key (`id`),
    foreign key (`author_id`) references `user` (`id`) on delete cascade
) engine = innodb default charset = utf8;

create table `comment` (
    `id` int unsigned not null auto_increment,
    `article_id` int unsigned not null,
    `author_id` int unsigned not null,
    `body` longtext not null,
    `created_at` datetime not null,
    primary key (`id`),
    foreign key (`article_id`) references `article` (`id`) on delete cascade,
    foreign key (`author_id`) references `user` (`id`) on delete cascade
) engine = innodb default charset = utf8;

create table `oauth_user` (
    `user_id` int unsigned not null,
    `provider` smallint unsigned not null,
    `identifier` varchar(255) not null,
    primary key (`user_id`, `provider`),
    unique key (`provider`, `identifier`),
    foreign key (`user_id`) references `user` (`id`) on delete cascade
) engine = innodb default charset = utf8;
