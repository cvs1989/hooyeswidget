CREATE TABLE [dbo].[Timeline](
	[MID] [int] NOT NULL,
	[DayID] [int] NOT NULL,
	[CreateDate] [datetime] NULL,
	[UpdateDate] [datetime] NULL,
	[Second] [float] NULL,
 CONSTRAINT [PK_Timeline] PRIMARY KEY CLUSTERED 
(
	[MID] ASC,
	[DayID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

ALTER TABLE [dbo].[Timeline] ADD  CONSTRAINT [DF_Timeline_CreateDate]  DEFAULT (getdate()) FOR [CreateDate]
GO

ALTER TABLE [dbo].[Timeline] ADD  CONSTRAINT [DF_Timeline_Second]  DEFAULT ((0)) FOR [Second]
GO