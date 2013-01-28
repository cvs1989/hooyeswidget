CREATE TABLE [dbo].[MessageQueue](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[MID] [int] NOT NULL,
	[DayID] [int] NOT NULL,
	[Phone] [varchar](50) NOT NULL,
	[Flag] [int] NOT NULL,
	[Message] [nvarchar](200) NOT NULL,
	[CreateDate] [datetime] NULL,
	[UpdateDate] [datetime] NULL
) ON [PRIMARY]

GO
ALTER TABLE [dbo].[MessageQueue] ADD  CONSTRAINT [DF_MessageQueue_Flag]  DEFAULT ((0)) FOR [Flag]
GO

ALTER TABLE [dbo].[MessageQueue] ADD  CONSTRAINT [DF_MessageQueue_CreateDate]  DEFAULT (getdate()) FOR [CreateDate]
GO