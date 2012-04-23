CREATE TABLE [dbo].[MemberImport](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[MID] [int] NULL,
	[Name] [varchar](50) NULL,
	[IDCard] [varchar](20) NOT NULL,
	[IDSN] [varchar](30) NOT NULL,
	[Year] [int] NOT NULL,
	[sType] [varchar](20) NOT NULL,
	[Type] [int] NOT NULL,
	[Phone] [varchar](50) NULL,
	[tstamp] [datetime] NULL,
 CONSTRAINT [PK_MemberImport] PRIMARY KEY CLUSTERED 
(
	[IDCard] ASC,
	[IDSN] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[MemberImport] ADD  CONSTRAINT [DF_MemberImport_tstamp]  DEFAULT (getdate()) FOR [tstamp]
GO


