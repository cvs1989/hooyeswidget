CREATE TABLE [dbo].[Courses](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[CID] [int] NOT NULL,
	[CName] [varchar](50) NOT NULL,
	[Name] [varchar](200) NOT NULL,
	[Type] [int] NOT NULL,
	[Year] [int] NOT NULL,
	[Cate] [int] NULL,
	[Sort] [int] NULL,
	[Teacher] [varchar](50) NULL,
	[ActMinutes] [decimal](18, 0) NULL,
	[Length] [decimal](18, 1) NULL,
	[Memo] [varchar](50) NULL,
 CONSTRAINT [PK_Courses] PRIMARY KEY CLUSTERED 
(
	[CID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
