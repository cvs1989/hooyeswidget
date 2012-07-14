DROP PROC [M_Get_InvoiceImportList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-12
-- Update date: 2012-07-14
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_InvoiceImportList]
	@SN decimal = 0
AS
	SELECT 
	    --[姓名] = M.Name,
		[身份证号]=MC.IDCard,
		[报名序号]=MC.IDSN,
		[收件人] = MC.Name,
		[金额] = MC.Amount,
		[发票抬头] = MC.Title,
		[电话] = MC.Tel,
		[地址] = LEFT(MC.Address,3) +'...',
		[邮编] = MC.Zip,
		[状态]= CASE MC.flag
				 WHEN 0 THEN '未处理'
				 WHEN 1 THEN '成功'
				 WHEN 2 THEN '重复,未处理'
				 WHEN 3 THEN '该学员未注册'
				END
	FROM InvoiceImport MC
		LEFT JOIN Member  M ON MC.IDCard = M.IDCard AND MC.IDSN = M.IDSN
	WHERE MC.SN = @SN
RETURN 0