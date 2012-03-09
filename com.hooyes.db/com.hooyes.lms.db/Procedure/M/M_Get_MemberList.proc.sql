DROP PROC [M_Get_MemberList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-03-06
-- Update date: 2012-03-06
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_MemberList]
	
AS
	SELECT 
		 IID = isnull(I.IID,0)
		,Status = isnull(R.Status,0)
		,minutes = isnull(R.minutes,0)
		,Score = isnull(R.Score,0)
		,ID = M.ID - 43
		,M.[MID]
		,M.[Name]
		,M.[IDCard]
		,M.[IDSN]
		,M.[Year]
		,M.[Type]
		,M.[Level]
		,M.[Phone]
		,M.[RegDate]
	FROM Member M
		left join Invoice I on I.MID = M.MID
		left join Report R on R.MID = M.MID
	WHERE M.MID > 10000
	ORDER BY M.ID desc
RETURN 0