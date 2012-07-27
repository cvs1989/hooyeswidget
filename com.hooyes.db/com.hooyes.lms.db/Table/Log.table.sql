CREATE TABLE [dbo].[Log](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[MID] [int] NULL,
	[Code] [int] NULL,
	[Message] [varchar](200) NULL,
	[tstamp] [datetime] NULL
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[Log] ADD  CONSTRAINT [DF_Log_tstamp]  DEFAULT (getdate()) FOR [tstamp]
GO