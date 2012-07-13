DROP PROC [M_Task_Invoice]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-12
-- Update date: 2012-07-12
-- Desc:
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
		  ,a.[Amount]
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