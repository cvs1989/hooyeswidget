DROP PROC [M_Get_CreditList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-26
-- Update date: 2012-04-26
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_CreditList]
	@SN decimal = 0
AS
	SELECT 
	    [姓名] = M.Name,
		[身份证号]=MC.IDCard,
		[报名序号]=MC.IDSN,
		[类型] = CASE M.Type 
				 WHEN 0 THEN '行政'
				 WHEN 1 THEN '企业'
				 END,
		[年份] = M.Year,
		[课时] = ISNULL(R.Minutes,0),
		[分数] = ISNULL(R.SCORE,0),
		[状态]= CASE MC.flag
				 WHEN 0 THEN '未处理'
				 WHEN 1 THEN '成功'
				 WHEN 2 THEN '重复,未处理'
				 WHEN 3 THEN '该学员未注册'
				END
	FROM MemberCredit MC
		LEFT JOIN Member  M ON MC.IDCard = M.IDCard AND MC.IDSN = M.IDSN
		LEFT JOIN Report  R ON M.MID = R.MID
	WHERE MC.SN = @SN
RETURN 0