CREATE TABLE [dbo].[Invoice](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[IID] [int] NOT NULL,
	[MID] [int] NOT NULL,
	[IDSN] [varchar](30) NOT NULL,
	[Name] [varchar](50) NOT NULL,
	[Title] [varchar](100) NOT NULL,
	[Tel] [varchar](20) NOT NULL,
	[Province] [varchar](10) NOT NULL,
	[City] [varchar](10) NOT NULL,
	[Address] [varchar](300) NOT NULL,
	[Zip] [varchar](10) NOT NULL,
 CONSTRAINT [PK_Invoice_1] PRIMARY KEY CLUSTERED 
(
	[IID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]