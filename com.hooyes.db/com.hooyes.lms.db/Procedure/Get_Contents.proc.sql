-- DROP PROC [Get_Contents]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2014-01-06
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Contents] @CID INT
AS 
    SELECT  cs.CID ,
            ct.[ID] ,
            ct.[CName] ,
            ct.[CCID] ,
            ct.[CCName] ,
            ct.[Name] ,
            ct.[Duration] ,
            ct.[Url]
    FROM    dbo.Contents ct
            INNER JOIN dbo.Courses cs ON cs.CName = ct.CName
    WHERE   cs.CID = @CID
    RETURN 0