CREATE TABLE [dbo].[Report](
	[MID] [int] NOT NULL,
	[Score] [int] NULL,
	[Compulsory] [decimal](18, 1) NULL,
	[Elective] [decimal](18, 1) NULL,
	[Status] [int] NULL,
	[Minutes] [decimal](18, 1) NULL,
	[Memo] [varchar](100) NULL,
	[CreateDate] [datetime] NULL,
	[UpdateDate] [datetime] NULL,
	[CommitDate] [datetime] NULL,
 CONSTRAINT [PK_Report] PRIMARY KEY CLUSTERED 
(
	[MID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[Report] ADD  CONSTRAINT [DF_Report_CreateDate]  DEFAULT (getdate()) FOR [CreateDate]
GO