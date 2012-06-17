CREATE TABLE [dbo].[OLM_MoneryList](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[ML_Number] [varchar](35) NOT NULL,
	[USN] [decimal](18, 0) NULL,
	[PUC] [varchar](50) NULL,
	[Pay_Amount] [money] NULL,
	[Pay_Time] [datetime] NULL,
	[Content] [varchar](200) NULL,
	[Status] [int] NULL,
	[Invoice_Status] [int] NULL,
	[Add_Time] [datetime] NULL,
 CONSTRAINT [PK_OLM_MoneryList] PRIMARY KEY CLUSTERED 
(
	[ML_Number] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

