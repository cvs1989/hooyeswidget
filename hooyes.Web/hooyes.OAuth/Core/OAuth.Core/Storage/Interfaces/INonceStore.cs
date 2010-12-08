using OAuth.Core.Interfaces;

namespace OAuth.Core.Storage.Interfaces
{
    /// <summary>
    /// A nonce store is used to avoid requests being "replayed".
    /// </summary>
    public interface INonceStore
    {
        /// <summary>
        /// Will record the none and check if it's unique.
        /// </summary>
        /// <param name="consumer">The consumer associated with the nonce</param>
        /// <param name="nonce">The nonce string itself</param>
        /// <returns><c>true</c> if the nonce is unique, <c>false</c> if the nonce has been presented before</returns>
        bool RecordNonceAndCheckIsUnique(IConsumer consumer, string nonce);
    }
}