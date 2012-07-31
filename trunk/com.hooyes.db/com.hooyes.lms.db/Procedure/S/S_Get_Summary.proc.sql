DROP PROC [S_Get_Summary]
GO
-- =============================================
-- Version:     1.0.0.0
-- Author:		hooyes
-- Create date: 2012-07-31
-- Update date: 2012-07-31
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[S_Get_Summary]

AS
	SELECT 
		'月份'  = Convert(varchar(10),MONTH(tstamp))+'月',
		'导入数量'= COUNT(1),
		'学完数量'= COUNT(case when flag=1 then 1 end)
	FROM  MemberCredit 
	WHERE tag = 100  
	GROUP BY MONTH(tstamp)
RETURN 0