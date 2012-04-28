DROP PROC [M_Get_MemberListCount]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-21
-- Update date: 2012-04-27
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_MemberListCount]
	@Filter varchar(700) = ''
AS
	SELECT COUNT(1) FROM v_m_member 
RETURN 0