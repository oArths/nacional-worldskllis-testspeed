create table artist
(
    id        int auto_increment
        primary key,
    name      text     not null,
    birthday  char(10) null,
    photoUrl  text     null,
    biography text     null
);

create table genre
(
    id    int auto_increment
        primary key,
    title text not null,
    constraint Genre_pk2
        unique (title) using hash
);

create table movie
(
    id              int auto_increment
        primary key,
    genreId         int      not null,
    title           text     not null,
    synopsis        text     null,
    durationMinutes int      null,
    releaseDate     char(10) null,
    posterUrl       text     null,
    trailerUrl      text     null,
    constraint Movie_pk2
        unique (title) using hash,
    constraint Movie_genre_id_fk
        foreign key (genreId) references genre (id)
            on update cascade on delete cascade
);

create table role
(
    id    int auto_increment
        primary key,
    title text not null,
    constraint Role_pk2
        unique (title) using hash
);

create table credit
(
    id       int auto_increment
        primary key,
    movieId  int not null,
    artistId int not null,
    roleId   int not null,
    constraint Credit_artist_id_fk
        foreign key (artistId) references artist (id)
            on update cascade on delete cascade,
    constraint Credit_movie_id_fk
        foreign key (movieId) references movie (id)
            on update cascade on delete cascade,
    constraint Credit_role_id_fk
        foreign key (roleId) references role (id)
            on update cascade on delete cascade
);

create table user
(
    id       int auto_increment
        primary key,
    name     text not null,
    email    text not null,
    username text not null,
    password text not null,
    constraint user_pk
        unique (email) using hash,
    constraint user_pk2
        unique (username) using hash
);

create table accesstoken
(
    id           int auto_increment
        primary key,
    userId       int                                   not null,
    tokenString  text                                  not null,
    creationDate timestamp default current_timestamp() not null on update current_timestamp(),
    constraint accessToken_user_id_fk
        foreign key (userId) references user (id)
            on update cascade on delete cascade
);

create table review
(
    id        int auto_increment
        primary key,
    userId    int                                   not null,
    movieId   int                                   not null,
    content   text                                  null,
    stars     int       default 0                   null,
    createdAt timestamp default current_timestamp() not null on update current_timestamp(),
    constraint Review_movie_id_fk
        foreign key (movieId) references movie (id)
            on update cascade on delete cascade,
    constraint Review_user_id_fk
        foreign key (userId) references user (id)
            on update cascade on delete cascade
);

create table reviewevaluation
(
    id       int auto_increment
        primary key,
    userId   int              not null,
    reviewId int              not null,
    positive bit default b'0' not null,
    constraint ReviewEvaluation_review_id_fk
        foreign key (reviewId) references review (id)
            on update cascade on delete cascade,
    constraint ReviewEvaluation_user_id_fk
        foreign key (userId) references user (id)
            on update cascade on delete cascade
);


