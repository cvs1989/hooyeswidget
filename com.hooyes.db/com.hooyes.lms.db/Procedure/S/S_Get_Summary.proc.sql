-- DROP PROC [S_Get_Summary]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-07-31
-- Update date: 2013-07-24
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[S_Get_Summary]
   @Tag int = 100,
   @Token varchar(100) =''
AS
    DECLARE @S_Token varchar(100) 
	SET @S_Token = sys.fn_VarBinToHexStr(hashbytes('md5',@Token))

	SELECT 
		'月份'  = Convert(varchar(10),MONTH(tstamp))+'月',
		'导入数量'= COUNT(1),
		'学完数量'= COUNT(case when flag=1 then 1 end)
	FROM  MemberCredit 
	WHERE tag = @Tag  
	AND Token = @S_Token
	GROUP BY MONTH(tstamp)
RETURN 0