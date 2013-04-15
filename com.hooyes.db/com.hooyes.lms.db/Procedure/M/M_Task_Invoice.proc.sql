DROP PROC [M_Task_Invoice]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-12
-- Update date: 2013-04-15
-- Desc: 当年的 80 其它年份 60 
-- =============================================
CREATE PROCEDURE [dbo].[M_Task_Invoice]
	@SN decimal = 0
AS
	INSERT INTO [Invoice]
			   ([IID]
			   ,[MID]
			   ,[IDSN]
			   ,[Name]
			   ,[Amount]
			   ,[Title]
			   ,[Tel]
			   ,[Province]
			   ,[City]
			   ,[Address]
			   ,[Zip]
			   ,[CreateDate])
	SELECT 
		   a.[IID]
		  ,b.[MID]
		  ,a.[IDSN]
		  ,a.[Name]
		  --,a.[Amount]
		  ,Amount = CASE 
					WHEN LEFT(a.[IDSN],2) = RIGHT(YEAR(GETDATE()),2) AND a.[Amount] = 0 THEN 60
					WHEN LEFT(a.[IDSN],2) != RIGHT(YEAR(GETDATE()),2) AND a.[Amount] = 0 THEN 80
					ELSE a.[Amount]
                  END
		  ,a.[Title]
		  ,a.[Tel]
		  ,a.[Province]
		  ,a.[City]
		  ,a.[Address]
		  ,a.[Zip]
		  ,a.[CreateDate]
	FROM  InvoiceImport a
	  inner join Member b on a.IDSN = b.IDSN and a.IDCARD = b.IDCard
	WHERE a.flag = 0 
	  and a.SN = @SN

    UPDATE InvoiceImport SET flag = 1 WHERE flag = 0 and SN = @SN
RETURN 0