import os
import tensorflow as tf
import tensorflow.python.platform
from tensorflow.python.platform import gfile
import numpy as np
import glob

classes = np.array(['ayam_bakar', 'ayam_crispy', 'bakso', 'gado2', 'ikan_bakar', 'mie_goreng', 'nasi_goreng', 'pecel_lele', 'pizza', 'rendang', 'sate', 'soto', 'sushi'])
num_classes = len(classes)
num_features = 2048

def create_graph(model_path):
    """
    create_graph loads the inception model to memory, should be called before
    calling extract_features.
 
    model_path: path to inception model in protobuf form.
    """
    with gfile.FastGFile(model_path, 'rb') as f:
        graph_def = tf.GraphDef()
        graph_def.ParseFromString(f.read())
        _ = tf.import_graph_def(graph_def, name='')

def extract_features(image_path, verbose=False):
    """
    extract_features computed the inception bottleneck feature for a list of images
 
    image_paths: array of image path
    return: 2-d array in the shape of (len(image_paths), 2048)
    """
    features = []
    cls = []
    
    with tf.Session() as sess:
        flattened_tensor = sess.graph.get_tensor_by_name('pool_3:0')
 
        image_data = gfile.FastGFile(image_path, 'rb').read()
        feature = sess.run(flattened_tensor, {
            'DecodeJpeg/contents:0': image_data
        })       
                
    return feature

path = 'inception_dec_2015/tensorflow_inception_graph.pb'
create_graph(path)

def new_weights(shape):
    return tf.Variable(tf.truncated_normal(shape, stddev=0.05))
def new_biases(length):
    return tf.Variable(tf.constant(0.05, shape=[length]))

def new_fc_layer(input,          # The previous layer.
                 num_inputs,     # Num. inputs from prev. layer.
                 num_outputs,    # Num. outputs.
                 use_relu=True): # Use Rectified Linear Unit (ReLU)?

    # Create new weights and biases.
    weights = new_weights(shape=[num_inputs, num_outputs])
    biases = new_biases(length=num_outputs)

    # Calculate the layer as the matrix multiplication of
    # the input and weights, and then add the bias-values.
    layer = tf.matmul(input, weights) + biases

    # Use ReLU?
    if use_relu:
        layer = tf.nn.relu(layer)

    return layer

feature_size_flat = num_features
x = tf.placeholder(tf.float32, shape=[None, feature_size_flat], name='x')
y_true = tf.placeholder(tf.float32, shape=[None, num_classes], name='y_true')
y_true_cls = tf.argmax(y_true, dimension=1)


layer_fc1 = new_fc_layer(input= x,
                         num_inputs=num_features,
                         num_outputs=1000,
                         use_relu=True)
layer_fc2 = new_fc_layer(input=layer_fc1,
                         num_inputs=1000,
                         num_outputs=num_classes,
                         use_relu=False)
y_pred = tf.nn.softmax(layer_fc2)
y_pred_cls = tf.argmax(y_pred, dimension=1)
cross_entropy = tf.nn.softmax_cross_entropy_with_logits(logits=layer_fc2,
                                                        labels=y_true)
cost = tf.reduce_mean(cross_entropy)
optimizer = tf.train.AdamOptimizer(learning_rate=1e-4).minimize(cost)
correct_prediction = tf.equal(y_pred_cls, y_true_cls)
accuracy = tf.reduce_mean(tf.cast(correct_prediction, tf.float32))

session = tf.Session()
save_path = "the_model/food_model"
saver = tf.train.Saver()
saver.restore(sess = session, save_path = save_path)

####################################################### LOOP HERE !!!!!!!!!!!! ###############################################################
image_path = 'data_testing/ayam_bakar/3.jpg'

# extracting the features for transfer learning
images = extract_features(image_path, verbose=False)
transfer_values = np.asarray(images)
transfer_values = transfer_values.reshape((1,2048))

feed_dict = {x: transfer_values}
hasil = y_pred_cls.eval(feed_dict, session = session)
ss = classes[hasil]
print (ss)
####################################################### <<<<<< END LOOP >>>>>>> ##############################################################

session.close 
