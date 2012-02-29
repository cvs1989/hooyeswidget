DROP PROC [Get_MemberList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2011-12-18
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MemberList]
	
AS
	SELECT * FROM Member WHERE MID > 10000
	ORDER BY ID desc
RETURN 0