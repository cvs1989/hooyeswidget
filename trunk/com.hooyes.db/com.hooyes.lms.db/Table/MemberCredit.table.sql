CREATE TABLE [dbo].[MemberCredit](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[SN] [decimal](18, 0) NOT NULL,
	[sID] [int] NULL,
	[MID] [int] NULL,
	[Name] [varchar](50) NULL,
	[IDCard] [varchar](20) NOT NULL,
	[IDSN] [varchar](30) NOT NULL,
	[Year] [int] NULL,
	[sType] [varchar](20) NULL,
	[Type] [int] NULL,
	[Phone] [varchar](20) NULL,
	[tstamp] [datetime] NULL,
	[flag] [int] NULL,
	[tag] [int] NULL,
 [Token] VARCHAR(100) NULL, 
    CONSTRAINT [PK_MemberCredit] PRIMARY KEY CLUSTERED 
(
	[SN] ASC,
	[IDCard] ASC,
	[IDSN] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[MemberCredit] ADD  CONSTRAINT [DF_MemberCredit_tstamp]  DEFAULT (getdate()) FOR [tstamp]
GO

ALTER TABLE [dbo].[MemberCredit] ADD  CONSTRAINT [DF_MemberCredit_flag]  DEFAULT ((0)) FOR [flag]
GO

