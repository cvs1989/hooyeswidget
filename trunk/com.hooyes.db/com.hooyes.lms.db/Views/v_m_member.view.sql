DROP VIEW [v_m_member]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-21
-- Update date: 2012-04-21
-- Desc:
-- =============================================
CREATE VIEW [dbo].[v_m_member]
AS 
	SELECT 
		 IID = isnull(I.IID,0)
		,Status = isnull(R.Status,0)
		,minutes = isnull(R.minutes,0)
		,Score = isnull(R.Score,0)
		,ID = ROW_NUMBER() OVER(order by M.MID asc)
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
