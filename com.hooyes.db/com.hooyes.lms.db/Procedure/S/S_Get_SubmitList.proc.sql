DROP PROC [S_Get_SubmitList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-23
-- Update date: 2012-07-23
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[S_Get_SubmitList]

AS
	SELECT 
	     M.MID
		,M.IDSN
		,M.IDCard
		,M.RegDate
		,M.Year
		,R.Score
		,Compulsory = ISNULL(R.Compulsory,8)
		,Elective = ISNULL(R.Elective,18)
		,Status = ISNULL(R.Status,0)
	FROM Member M
		inner join MemberCredit MC ON M.MID = MC.MID and MC.flag = 1 and MC.tag = 100
		inner join Report R ON R.MID = M.MID and (R.Status = 0 OR R.Status is null)
	WHERE DATEDIFF(HOUR,M.RegDate,GETDATE())>= 36
RETURN 0