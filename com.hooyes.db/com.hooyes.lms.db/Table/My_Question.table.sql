CREATE TABLE [dbo].[My_Question](
	[MID] [int] NOT NULL,
	[QID] [int] NOT NULL,
	[Flag] [int] NOT NULL,
	[Answer] [nvarchar](50) NOT NULL,
	[Score] [int] NOT NULL,
	[Status] [int] NULL,
 CONSTRAINT [PK_My_Question] PRIMARY KEY CLUSTERED 
(
	[MID] ASC,
	[QID] ASC,
	[Flag] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO