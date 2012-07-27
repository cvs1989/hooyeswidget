CREATE TABLE [dbo].[My_Courses](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[MID] [int] NOT NULL,
	[CID] [int] NOT NULL,
	[Minutes] [int] NOT NULL,
	[Second] [decimal](18, 0) NOT NULL,
	[Status] [int] NOT NULL,
	[Validate] [int] NULL,
 CONSTRAINT [PK_My_Courses] PRIMARY KEY CLUSTERED 
(
	[MID] ASC,
	[CID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

ALTER TABLE [dbo].[My_Courses] ADD  CONSTRAINT [DF_My_Courses_Minutes]  DEFAULT ((0)) FOR [Minutes]
GO

ALTER TABLE [dbo].[My_Courses] ADD  CONSTRAINT [DF_My_Courses_Status]  DEFAULT ((0)) FOR [Status]
GO