DROP PROC [Get_MemberList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-29
-- Update date: 2012-02-29
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MemberList]
	
AS
	SELECT * FROM Member 
	ORDER BY ID desc
RETURN 0