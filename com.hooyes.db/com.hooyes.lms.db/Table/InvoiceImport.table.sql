CREATE TABLE [dbo].[InvoiceImport](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[IID] [int] NULL,
	[SN] [decimal](18, 0) NOT NULL,
	[IDCard] [varchar](20) NOT NULL,
	[IDSN] [varchar](30) NOT NULL,
	[Name] [varchar](50) NOT NULL,
	[Amount] [money] NOT NULL,
	[Title] [varchar](100) NOT NULL,
	[Tel] [varchar](20) NOT NULL,
	[Province] [varchar](10) NOT NULL,
	[City] [varchar](10) NOT NULL,
	[Address] [varchar](300) NOT NULL,
	[Zip] [varchar](10) NOT NULL,
	[CreateDate] [datetime] NULL,
	[flag] [int] NULL,
 CONSTRAINT [PK_InvoiceImport] PRIMARY KEY CLUSTERED 
(
	[SN] ASC,
	[IDCard] ASC,
	[IDSN] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[InvoiceImport] ADD  CONSTRAINT [DF_InvoiceImport_CreateDate]  DEFAULT (getdate()) FOR [CreateDate]
GO