-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2014-05-22
-- Update date: 2014-05-22
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_Region] @AID INT = 0
AS 
    SELECT  r.*
    FROM    dbo.Region r
            INNER JOIN dbo.AdminRegions ar ON ar.RegionCode = r.Code
    WHERE   ar.AID = @AID
    RETURN 0
