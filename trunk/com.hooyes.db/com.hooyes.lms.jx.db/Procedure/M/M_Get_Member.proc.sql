-- DROP PROC [M_Get_Member]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-03-03
-- Update date: 2012-03-06
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_Member]
	@keyword varchar(30) 
AS
	SELECT 
		 TOP 100
		 IID = isnull(I.IID,0)
		,Status = isnull(R.Status,0)
		,minutes = isnull(R.minutes,0)
		,Score = isnull(R.Score,0)
		,M.*
	FROM Member M
		left join Invoice I on I.MID = M.MID
		left join Report R on R.MID = M.MID
	WHERE (M.IDSN  like @keyword OR M.IDCard like @keyword OR M.Name like @keyword)
RETURN 0