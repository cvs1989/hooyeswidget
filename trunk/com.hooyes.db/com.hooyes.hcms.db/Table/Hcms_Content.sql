CREATE TABLE [dbo].[Hcms_Content]
(
	[CID] INT NOT NULL PRIMARY KEY, 
    [Title] NVARCHAR(100) NULL, 
    [Content] NVARCHAR(MAX) NULL, 
	[Author] NVARCHAR(50) NULL,
    [CreateDate] DATETIME NULL, 
    [UpdateDate] DATETIME NULL
)
