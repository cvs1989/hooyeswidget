DROP PROC [M_Get_InvoiceImportList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-12
-- Update date: 2013-04-15
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_InvoiceImportList]
	@SN decimal = 0
AS
	SELECT 
		--[姓名] = M.Name,
		[身份证号]=MC.IDCard,
		[报名序号]=MC.IDSN,
		[收件人] = CASE WHEN MC.flag = 2 THEN I.Name
				   ELSE MC.Name
				   END,
		[金额] =  I.Amount,
		[发票抬头] = CASE WHEN MC.flag = 2 THEN I.Title
					ELSE MC.Title
					END ,
		[电话] = CASE WHEN MC.flag = 2 THEN I.Tel
				 ELSE MC.Tel
				 END ,
		[地址] = CASE WHEN MC.flag = 2 THEN I.Address
				ELSE MC.Address
				END,
		[邮编] = MC.Zip,
		[状态]= CASE MC.flag
				 WHEN 0 THEN '未处理'
				 WHEN 1 THEN '成功'
				 WHEN 2 THEN '重复,未处理'
				 WHEN 3 THEN '该学员未注册'
				END
	FROM InvoiceImport MC
		LEFT JOIN Member  M ON MC.IDCard = M.IDCard AND MC.IDSN = M.IDSN
		LEFT JOIN Invoice I ON I.MID = M.MID
	WHERE MC.SN = @SN
RETURN 0