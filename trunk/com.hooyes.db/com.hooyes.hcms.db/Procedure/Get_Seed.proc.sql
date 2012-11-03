DROP PROC [Get_Seed]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-11-03
-- Update date: 2012-11-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Seed]
	@ID int = 1, 
	@Value int output
AS
	UPDATE Hcms_Seed SET Value=Value+1 WHERE ID = @ID
    SELECT @Value = Value FROM Hcms_Seed WHERE ID = @ID
RETURN 0