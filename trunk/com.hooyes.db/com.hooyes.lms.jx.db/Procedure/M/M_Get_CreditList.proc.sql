-- DROP PROC [M_Get_CreditList]
GO
-- =============================================
-- Verion:      1.0.0.1
-- Author:		hooyes
-- Create date: 2012-04-26
-- Update date: 2014-01-10
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_CreditList] @SN DECIMAL = 0
AS 
    SELECT  [姓名] = ISNULL(M.Name, MC.Name) ,
            [身份证号] = MC.IDCard ,
            [年份] = MC.Year ,
            [课时] = ISNULL(R.Minutes, 0) ,
            [分数] = ISNULL(R.SCORE, 0) ,
            [状态] = CASE MC.flag
                     WHEN 0 THEN '未处理'
                     WHEN 1 THEN '成功'
                     WHEN 2 THEN '重复,未处理'
                     WHEN 3 THEN '该学员未注册'
                   END
    FROM    MemberCredit MC
            LEFT JOIN Member M ON MC.IDCard = M.IDCard
            LEFT JOIN Report R ON M.MID = R.MID
                                  AND MC.Year = R.Year
    WHERE   MC.SN = @SN
	ORDER BY MC.ID
    RETURN 0