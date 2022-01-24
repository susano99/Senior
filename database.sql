/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     8/29/2021 10:41:12 PM                        */
/*==============================================================*/

/*==============================================================*/
/* Table: COURSES                                               */
/*==============================================================*/
create table COURSES
(
   COURSEID             int not null  comment '',
   CODE                 varchar(20)  comment '',
   LANGUAGE             varchar(20)  comment '',
   NAME                 varchar(20)  comment '',
   ISELECTIVE           bool  comment '',
   SEMESTER             varchar(20)  comment '',
   FULLHOURS            int  comment '',
   COURSEHOURS          int  comment '',
   TPHOURS              int  comment '',
   TDHOURS              int  comment '',
   NBOFCREDITS          int  comment '',
   DEPARTMENT           varchar(20)  comment '',
   DESCRIPTION          varchar(30)  comment '',
   primary key (COURSEID)
);

/*==============================================================*/
/* Table: DOCTORS                                               */
/*==============================================================*/
create table DOCTORS
(
   DOCTORID             int not null  comment '',
   FIRSTNAME            varchar(30)  comment '',
   FATHERNAME           varchar(30)  comment '',
   LASTNAME             varchar(30)  comment '',
   PHONE                bigint  comment '',
   EMAIL                varchar(30)  comment '',
   ACADEGREE            varchar(30)  comment '',
   CONTRACTTYPE         varchar(30)  comment '',
   primary key (DOCTORID)
);

/*==============================================================*/
/* Table: REGISTRATION                                          */
/*==============================================================*/
create table REGISTRATION
(
   ID                   int not null auto_increment  comment '',
   DOCTORID             int not null  comment '',
   COURSEID             int not null  comment '',
   CRSHRS               int  comment '',
   TPHRS                int  comment '',
   TDHRS                int  comment '',
   YEAR                 varchar(15)  comment '',
   primary key (ID)
    constraint FK_REGISTRA_REGISTRAT_DOCTORS foreign key (DOCTORID)
      references DOCTORS (DOCTORID) on delete restrict on update restrict,
   constraint FK_REGISTRA_REGISTRAT_COURSES foreign key (COURSEID)
      references COURSES (COURSEID) on delete restrict on update restrict
);

/*==============================================================*/
/* Table: USERS                                                 */
/*==============================================================*/
create table USERS
(
   ID                   int not null auto_increment  comment '',
   USERNAME             varchar(20)  comment '',
   PASSWORD             varchar(20)  comment '',
   FULLNAME             varchar(30)  comment '',
   ROLE                 varchar(20)  comment '',
   EMAIL                varchar(20)  comment '',
   primary key (ID)
);

