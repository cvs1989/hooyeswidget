CREATE TABLE [dbo].[Contents](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[CID] [int] NOT NULL,
	[CCID] [int] NOT NULL,
	[CCName] [varchar](50) NOT NULL,
	[Name] [varchar](100) NOT NULL,
	[Duration] [int] NOT NULL,
	[Url] [varchar](300) NOT NULL,
 CONSTRAINT [PK_Contents_1] PRIMARY KEY CLUSTERED 
(
	[CID] ASC,
	[CCID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[Contents] ADD  CONSTRAINT [DF_Contents_Duration]  DEFAULT ((0)) FOR [Duration]
GO
