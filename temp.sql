drop table if exists articleTag;
drop table if exists article;
drop table if exists tag;
drop table if exists author;

create table author(
    authorId binary(16) not null,
    authorActivationToken char(32),
    authorAvatarUrl varchar(255),
    authorEmail varchar(128) not null,
    authorHash char(97) not null,
    authorUsername varchar(32) not null,
    unique(authorEmail),
    unique(authorUsername),
    index(authorEmail),
    primary key(authorId)
);

create table tag(
    tagId binary(16) not null,
    tagName varchar(32) not null,
    primary key(tagId)
);

create table article(
    articleId binary(16) not null,
    articleAuthorId binary(16) not null,
    articleContent varchar(40000) not null,
    articleDate datetime(6) not null,
    articleImage varchar(255),
    index(articleAuthorId),
    foreign key(articleAuthorId) references author(authorId),
    primary key(articleId)
);

create table articleTag(
    articleTagArticleId binary(16),
    articleTagTagId binary(16),
    index(articleTagArticleId),
    index(articleTagTagId),
    foreign key(articleTagArticleId) references article(articleId),
    foreign key(articleTagTagId) references tag(tagId),
    primary key(articleTagArticleId, articleTagTagId)
);