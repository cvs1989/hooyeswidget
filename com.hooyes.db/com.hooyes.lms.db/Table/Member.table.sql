CREATE TABLE [dbo].[Member](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[MID] [int] NOT NULL,
	[Name] [varchar](50) NULL,
	[IDCard] [varchar](20) NOT NULL,
	[IDSN] [varchar](30) NOT NULL,
	[Year] [int] NULL,
	[Type] [int] NULL,
	[Level] [int] NULL,
	[Phone] [varchar](20) NULL,
	[RegDate] [datetime] NULL,
	[ExpireDate] [datetime] NULL,
	[Tag] [int] NULL,
 CONSTRAINT [PK_Member] PRIMARY KEY CLUSTERED 
(
	[MID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[Member] ADD  CONSTRAINT [DF_Member_Type]  DEFAULT ((0)) FOR [Type]
GO

ALTER TABLE [dbo].[Member] ADD  CONSTRAINT [DF_Member_Level]  DEFAULT ((0)) FOR [Level]
GO

ALTER TABLE [dbo].[Member] ADD  CONSTRAINT [DF_Member_RegDate]  DEFAULT (getdate()) FOR [RegDate]
GO

ALTER TABLE [dbo].[Member] ADD  CONSTRAINT [DF_Member_Tag]  DEFAULT ((0)) FOR [Tag]
GO