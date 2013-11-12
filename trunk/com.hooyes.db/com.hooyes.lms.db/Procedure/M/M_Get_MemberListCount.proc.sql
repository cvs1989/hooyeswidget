-- DROP PROC [M_Get_MemberListCount]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-21
-- Update date: 2012-05-01
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_MemberListCount]
	@Filter varchar(700) = ''
AS
	IF @Filter = ''
		SELECT COUNT(1) FROM v_m_member 
	ELSE
	BEGIN
		DECLARE @SQL varchar(800)
		SET @SQL = 'SELECT COUNT(1) FROM v_m_member WHERE ' + @Filter
		EXECUTE(@SQL)
	END
RETURN 0