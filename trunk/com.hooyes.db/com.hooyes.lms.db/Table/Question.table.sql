CREATE TABLE [dbo].[Question](
	[QID] [int] NOT NULL,
	[CID] [int] NOT NULL,
	[Subject] [nvarchar](200) NOT NULL,
	[A] [nvarchar](100) NULL,
	[B] [nvarchar](100) NULL,
	[C] [nvarchar](100) NULL,
	[D] [nvarchar](100) NULL,
	[Answer] [nvarchar](50) NOT NULL,
	[Score] [int] NOT NULL,
	[Cate] [int] NULL,
 CONSTRAINT [PK_Question] PRIMARY KEY CLUSTERED 
(
	[QID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
