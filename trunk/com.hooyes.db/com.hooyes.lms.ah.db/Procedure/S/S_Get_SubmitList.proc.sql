-- DROP PROC [S_Get_SubmitList]
GO
-- =============================================
-- Version:     1.0.0.8
-- Author:		hooyes
-- Create date: 2012-07-23
-- Update date: 2013-06-17
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[S_Get_SubmitList]
	@count int = 22
AS

	UPDATE TOP(5) Member
	SET Tag = 0 
	WHERE Tag = 100
	and DATEDIFF(DAY,regdate,GETDATE())>5

	SELECT 
		TOP(@count)
	     M.MID
		,M.IDSN
		,M.IDCard
		,M.RegDate
		,M.Year
		,Score    = ISNULL(R.Score,0)
		,Compulsory = ISNULL(R.Compulsory,8)
		,Elective = ISNULL(R.Elective,18)
		,Status = ISNULL(R.Status,0)
	FROM Member M
		inner join MemberCredit MC ON M.MID = MC.MID and MC.flag = 1 and MC.tag = 100
		inner join Report R ON R.MID = M.MID and (R.Status = 0 OR R.Status is null)
	WHERE DATEDIFF(HOUR,M.RegDate,GETDATE())>= 20
		  and (M.ExpireDate is null or M.ExpireDate>=GETDATE())
	ORDER BY NEWID()
RETURN 0