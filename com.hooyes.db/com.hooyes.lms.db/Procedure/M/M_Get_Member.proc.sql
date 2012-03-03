DROP PROC [M_Get_Member]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-03-03
-- Update date: 2012-03-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_Member]
	@keyword varchar(30) 
AS
	SELECT TOP 10 * from Member where (IDSN  like @keyword OR IDCard like @keyword OR Name like @keyword)
RETURN 0